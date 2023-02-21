<?php

class UserController
{
    public function __construct(private UserService $userService)
    {
    }

    public function processRequest(string $method, ?string $id)
    {
        return $id ? $this->processResourceRequest($method, $id) : $this->processCollectionRequest($method);
    }

    private function processResourceRequest(string $method, string $id)
    {
        $user = $this->userService->get($id);
        $result = ["message" => ""];
        if (!$user) {
            http_response_code(404);
            $result["message"] = "User not found";
        } else {
            switch ($method) {
                case "PUT":
                    $data = json_decode(file_get_contents("php://input"), true);

                    $rows = $this->userService->update($user, $data);
                    $result["message"] = "User $id id updated";
                    $result["rows"] = $rows;
                    break;
                case "DELETE":
                    $this->userService->delete($id);
                    $result["message"] = "User $id id deleted";
                    break;
                default:
                    http_response_code(405);
                    header("Allow: POST, PUT, DELETE");
                    $result["message"] = "url not allow";
                    break;
            }
        }

        echo json_encode($result);
    }

    private function processCollectionRequest(string $method)
    {
        $result = ["message" => ""];

        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $this->userService->create($data);
            http_response_code(201);
            $result = [
                "message" => "create user succesfully",
                "id" => $id
            ];
        } else {
            http_response_code(405);
            header("Allow: POST, PUT, DELETE");
            $result["message"] = "url not found";
        }

        echo json_encode($result);
    }
}
